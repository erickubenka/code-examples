/*
 * Copyright (c) 2020 Eric Kubenka.
 * Licensed under the MIT License.
 * See LICENSE.md file in the project root for full license information.
 */
package de.codefever.junit.inject;

import com.google.inject.AbstractModule;
import com.google.inject.Scopes;
import de.codefever.junit.storage.StorageInterface;
import de.codefever.junit.storage.HashMapStorage;

/**
 * @author Eric Kubenka
 * creation date: 09.07.2021 - 08:44
 */
public final class JunitModule extends AbstractModule {

    protected void configure() {
        bind(StorageInterface.class).to(HashMapStorage.class).in(Scopes.SINGLETON);
    }
}

